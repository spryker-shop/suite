import register from 'ShopUi/app/registry';
export default register(
    'product-cart-items-list',
    () =>
        import(
            /* webpackMode: "eager" */
            'CartPage/components/molecules/cart-items-list/cart-items-list'
        ),
);
