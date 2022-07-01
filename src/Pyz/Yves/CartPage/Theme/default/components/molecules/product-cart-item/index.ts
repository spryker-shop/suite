import 'CartPage/components/molecules/product-cart-item/style';
import register from 'ShopUi/app/registry';
export default register(
    'product-cart-item',
    () =>
        import(
            /* webpackMode: "lazy" */
            './product-cart-item'
        ),
);
