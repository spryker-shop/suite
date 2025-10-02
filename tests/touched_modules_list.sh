set -euo pipefail

REPO="$1" # "spryker" or "spryker-shop"
BASE_DIR="vendor/spryker/${REPO}"

# Pick a safe base ref (prefer origin/HEAD; fallback to origin/master)
BASE_REF="$(git -C "${BASE_DIR}" symbolic-ref -q --short refs/remotes/origin/HEAD 2>/dev/null || true)"
: "${BASE_REF:=origin/master}"

# Collect changed files once
mapfile -t changed_files < <(git -C "${BASE_DIR}" diff --name-only --diff-filter=ACMRTUXB "${BASE_REF}"...)

# Early exit if nothing changed
((${#changed_files[@]} == 0)) && exit 0

# Extract module names from changed paths under Bundles/ or Features/
mapfile -t modules < <(
  printf '%s\n' "${changed_files[@]}" \
    | grep -E '^(Bundles|Features)/' \
    | cut -d'/' -f2 \
    | sort -u
)

for module in "${modules[@]}"; do
  # Skip obvious non-module “names”
  case "${module}" in
    *.xml|*.json|*.yml|*.yaml|*.md|*.txt) continue ;;
  esac

  if [[ "${REPO}" == "spryker" ]]; then
    # Verify the directories actually exist before emitting
    if [[ -d "${BASE_DIR}/Bundles/${module}" ]]; then
      echo "Spryker.${module}"
      continue
    fi
    if [[ -d "${BASE_DIR}/Features/${module}" ]]; then
      echo "SprykerFeature.${module}"
      continue
    fi
  else
    # spryker-shop: emit only if the bundle directory exists
    if [[ -d "${BASE_DIR}/Bundles/${module}" ]]; then
      echo "SprykerShop.${module}"
      continue
    fi
  fi
  # Optional: debug noise that would have been emitted
  # >&2 echo "Skipping ${REPO} module '${module}' (no matching directory)"
done
