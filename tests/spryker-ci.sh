#!/usr/bin/env bash

set -e

# PhpStan tool
phpStan() {
    local module_paths=$1

    mkdir -p ./data/.ci/phpstan

    for path in $module_paths; do
        if [[ ! -d "$path/src" ]]; then
            continue
        fi
        local neonFiles=()
        local level=7
        local pathToMergedNeon="data/.ci/phpstan/phpstan-temp-$(basename "$path" | tr '[:upper:]' '[:lower:]').neon"

        if [[ -f "$path/phpstan.neon" ]]; then
            neonFiles+=("$path/phpstan.neon")
            if grep -qE '^\s*level\s*:' "$path/phpstan.neon"; then
              level=$(grep -E '^\s*level\s*:' "$path/phpstan.neon" | sed -E 's/.*level\s*:\s*//')
            fi
        fi
        if [[ -f "$path/../../phpstan.neon" ]]; then
            neonFiles+=("$path/../../phpstan.neon")
            if grep -qE '^\s*level\s*:' "$path/../../phpstan.neon"; then
              level=$(grep -E '^\s*level\s*:' "$path/../../phpstan.neon" | sed -E 's/.*level\s*:\s*//')
            fi
        fi
        # create neon file to merge vendor config and module one
        {
          echo "includes:"
          for file in "${neonFiles[@]}"; do
            echo "    - ../../../$file"
          done
        } > "$pathToMergedNeon"
        chmod +r $pathToMergedNeon

        if ! run_command "vendor/bin/phpstan analyze --memory-limit=4000M --no-progress -c $pathToMergedNeon  $path/src -l $level" "$(basename "$path")"; then
            EXITCODE=1
            FAILED_MODULES+=("$path")
        fi
    done
}

parse_diff_output() {
    awk '
        /"name": *"spryker[^"]*\/[^"]+"/ {
            name_found = 1
            package_name = ""
            if ($0 ~ /"name": *"([^"]+)"/) {
                split($0, parts, "\"")
                package_name = parts[4]
            }
        }
        /"reference": *"[^"]+"/ && name_found {
            if ($0 ~ /"reference": *"([^"]+)"/) {
                split($0, parts, "\"")
                reference = parts[4]
                print package_name
            }
            name_found = 0
        }
    '
}

Codeception() {
    local module_paths=$1
    local option=$2

    for path in $module_paths; do
        if [[ ! -f "$path/codeception.yml" ]]; then
            continue
        fi
        if ! run_command "vendor/bin/codecept run -c $path $option" "$(basename "$path")"; then
            EXITCODE=1
            FAILED_MODULES+=("$path")
        fi
    done
}

# Returns all changed packages that starts from spryker*
get_changed_packages_on_project_level() {
      # Processing staged changes
      git diff --cached composer.lock | parse_diff_output
      # Processing changes in work directory
      git diff composer.lock | parse_diff_output
      # Processing diff with master
      git diff "$(git merge-base master HEAD)"...HEAD -- composer.lock | parse_diff_output
}

convert_package_name_to_camel_case() {
    local package_name=$1
    local org repo
    IFS='/' read -r org repo <<< "$package_name"
    org=$(echo "$org" | awk -F'[-_]' '{OFS=""; for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) tolower(substr($i,2)); print}')
    repo=$(echo "$repo" | awk -F'[-_]' '{OFS=""; for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) tolower(substr($i,2)); print}')
    echo "${org}.${repo}"
}

# Change PackageName to package-name format
to_kebab_case() {
  echo "$1" \
    | sed -E 's/([a-z0-9])([A-Z])/\1-\2/g' \
    | sed -E 's/([A-Z]+)([A-Z][a-z])/\1-\2/g' \
    | tr '[:upper:]' '[:lower:]'
}

# Extract changed packages from nonsplit repos
extract_modules_from_nonsplit_repo() {
  local package=$1
  local module_directory=$2
  MODULES=$(git -C ./vendor/${package} diff --name-only --diff-filter=ACMRTUXB master... | grep "^$module_directory\/" | cut -d "/" -f2- | cut -d "/" -f1 | sort | uniq)
  for module in $MODULES
        do
            echo "${package#*/}/$(to_kebab_case $module)"
        done
  wait
}

# Extract changed module paths from nonsplit repos
extract_module_paths_from_nonsplit_repo() {
  local package=$1
  local module_directory=$2
#  echo $(find "vendor/$package/$module_directory" -mindepth 1 -maxdepth 1 -type d)  # All modules
  echo $(git -C ./vendor/${package} diff --name-only --diff-filter=ACMRTUXB master... | grep "^$module_directory\/" | cut -d "/" -f1,2 | sort | uniq | sed "s|^|vendor/${package}/|")
}

# Returns changed Spryker packages for the project
get_changed_package_paths() {
    local changedPackages=();
    for packageNoneSplit in $(extract_module_paths_from_nonsplit_repo "spryker/spryker" Bundles); do
        changedPackages+=("$packageNoneSplit")
    done
    for packageNoneSplit in $(extract_module_paths_from_nonsplit_repo "spryker/spryker" Features); do
        changedPackages+=("$packageNoneSplit")
    done
    for packageNoneSplit in $(extract_module_paths_from_nonsplit_repo "spryker/spryker-shop" Bundles); do
        changedPackages+=("$packageNoneSplit")
    done
#    for package in $(get_changed_packages_on_project_level | sort -u); do
#      if [[ "$package" == "spryker/spryker" ]]; then
#        for packageNoneSplit in $(extract_module_paths_from_nonsplit_repo $package Bundles); do
#            changedPackages+=("$packageNoneSplit")
#        done
#        for packageNoneSplit in $(extract_module_paths_from_nonsplit_repo $package Features); do
#            changedPackages+=("$packageNoneSplit")
#        done
#      elif [[ "$package" == "spryker/spryker-shop" ]]; then
#          for packageNoneSplit in $(extract_module_paths_from_nonsplit_repo $package Bundles); do
#              changedPackages+=("$packageNoneSplit")
#          done
##      else
##        changedPackages+=("$package")
#      fi
#    done
    echo "${changedPackages[@]}" | tr ' ' '\n' | sort | uniq
}
# Save file with changed module paths
create_file_with_changed_module_paths() {
    local filePath=$1
    paths=$(get_changed_package_paths)
    mkdir -p ./data/.ci
    if (( ${#paths[@]} > 0 )); then
        printf "%s\n" "${paths[@]}" > $filePath
    else
        : > $filePath
    fi
}

# Read modules from file with offset and limit if they're provided
get_core_command_list() {
    local organization=$1
    local offset=$2
    local limit=$3
    local file="./data/.ci/changed_module_paths.txt"

    create_file_with_changed_module_paths "$file"

    if [[ ! -s "$file" ]]; then
        printf ""
        return
    fi

    local all_paths=()

    if [[ "$organization" == "all" ]]; then
        mapfile -t all_paths < "$file"
    else
        local path_prefix=""
        case "$organization" in
            Spryker)
                path_prefix="vendor/spryker/spryker/Bundles"
                ;;
            SprykerShop)
                path_prefix="vendor/spryker/spryker-shop/Bundles"
                ;;
            SprykerFeature)
                path_prefix="vendor/spryker/spryker/Features"
                ;;
            *)
                echo "❌ Unknown organization: '$organization'. Available: Spryker, SprykerShop, SprykerFeature, all" >&2
                return 1
                ;;
        esac

        while IFS= read -r line; do
            if [[ "$line" == "$path_prefix/"* ]]; then
                all_paths+=("$line")
            fi
        done < "$file"
    fi

    local start_index=0
    local end_index=${#all_paths[@]}

    if [[ "$offset" != "null" ]]; then
        start_index=$((offset - 1))
    fi

    if [[ "$limit" != "null" ]]; then
        end_index=$((start_index + limit))
        if (( end_index > ${#all_paths[@]} )); then
            end_index=${#all_paths[@]}
        fi
    fi

    local paginated_paths=()
    for ((i = start_index; i < end_index; i++)); do
        if (( i >= 0 && i < ${#all_paths[@]} )); then
            paginated_paths+=("${all_paths[i]}")
        fi
    done

    printf '%s\n' "${paginated_paths[@]}"
}

run_command() {
    printf '%*s\n' "100" '' | tr ' ' '-'
    printf 'Module: %s\nCommand: %s\n' "$2" "$1"
    printf '%*s\n' "100" '' | tr ' ' '-'
    php -d memory_limit=-1 $1
}

EXITCODE=0
FAILED_MODULES=()

command="$1"
organization="$2" # Spryker, SprykerShop, SprykerFeature, all
offset="${3:-null}"
limit="${4:-null}"
option="${5:-null}"

if [[ -z "$1" ]]; then
  echo "Error: command $0 [organisation] [offset] [limit] is missing."
  exit 1
fi
if ! declare -f "$command" > /dev/null; then
  echo "Error: the command tool '$command' is not found. Available: PhpStan."
  exit 1
fi

module_paths=$(get_core_command_list "$organization" "$offset" "$limit")
"$command" "$module_paths" "$option"

readarray -t array_modules <<< "$module_paths"
echo "📦 ${#array_modules[@]} touched module(s)."

if [ ${#FAILED_MODULES[@]} -gt 0 ]; then
  echo "❌ The following module(s) failed:"
  for module in "${FAILED_MODULES[@]}"; do
    echo " - $module"
  done
else
  echo "✅ All module(s) passed."
fi

exit $EXITCODE
