/* tslint:disable: no-any */
declare const require: any;
/* tslint:enable */

// add polyfills
let coreJsFeaturesFolder = 'fn';
/* tslint:disable: no-var-requires no-require-imports */
import { info } from 'ShopUi/app/logger';
try {
    require('core-js/features');
    coreJsFeaturesFolder = 'features';
} catch (e) {
    info('Please update the "core-js" version to >=3');
}
require(`core-js/${coreJsFeaturesFolder}/promise`);
require(`core-js/${coreJsFeaturesFolder}/array`);
require(`core-js/${coreJsFeaturesFolder}/set`);
require(`core-js/${coreJsFeaturesFolder}/map`);
/* tslint:enable */

import 'whatwg-fetch';
import 'element-remove';
import 'classlist-polyfill';
import 'string.prototype.startswith';
import 'date-input-polyfill';
import 'intersection-observer';
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
