import 'ProductSearchWidget/components/molecules/product-quick-add-form/style';
import register from 'ShopUi/app/registry';
export default register(
    'product-quick-add-form',
    () =>
        import(
            /* webpackMode: "lazy" */
            './product-quick-add-form'
        ),
);
