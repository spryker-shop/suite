/* tslint:disable: no-any */
declare const require: any;
/* tslint:enable */

// add polyfills
let coreJsPath = 'fn';
/* tslint:disable: no-var-requires no-require-imports */
try {
    require('core-js/features');
    coreJsPath = 'features';
} catch (e) {
    console.info('Please update the "core-js" version to >=3');
}
require(`core-js/${coreJsPath}/promise`);
require(`core-js/${coreJsPath}/array`);
require(`core-js/${coreJsPath}/set`);
require(`core-js/${coreJsPath}/map`);
/* tslint:enable */

import 'classlist-polyfill';
import 'string.prototype.startswith';
import 'date-input-polyfill';
import elementClosestPolyfill from 'element-closest';
elementClosestPolyfill(window);

// then load a shim for es5 transpilers (typescript or babel)
// https://github.com/webcomponents/webcomponentsjs#custom-elements-es5-adapterjs
/* tslint:disable: no-var-requires no-require-imports */
require('@webcomponents/webcomponentsjs/custom-elements-es5-adapter.js');
// add webcomponents polyfill
try {
    require('@webcomponents/webcomponents-platform/webcomponents-platform');
    require('@webcomponents/custom-elements/custom-elements.min');
} catch (e) {
    require('@webcomponents/webcomponentsjs/webcomponents-bundle');
}
/* tslint:enable */
