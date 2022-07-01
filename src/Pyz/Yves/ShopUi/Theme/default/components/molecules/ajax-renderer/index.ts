import register from 'ShopUi/app/registry';
export default register(
    'ajax-renderer',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "ajax-renderer" */
            './ajax-renderer'
        ),
);
