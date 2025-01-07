modules=$(git -C vendor/spryker/$1 diff --name-only --diff-filter=ACMRTUXB master... | grep -E "^Bundles\/|^Features\/" | cut -d "/" -f2- | cut -d "/" -f1 | sort | uniq)

for module in $modules; do
    if git -C vendor/spryker/$1 diff --name-only --diff-filter=ACMRTUXB master... | grep -q "^Bundles\/$module"; then
        echo "Spryker.$module"
    elif git -C vendor/spryker/$1 diff --name-only --diff-filter=ACMRTUXB master... | grep -q "^Features\/$module"; then
        if [[ -d "Features/$module/src/" || -d "Features/$module/tests/" ]]; then
            echo "SprykerFeature.$module"
        fi
    fi
done
