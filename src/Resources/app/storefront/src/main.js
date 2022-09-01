import CgsPlugin from "./cgs-plugin/cgs-plugin.plugin";
import MyCookiePermission from './my-cookie-permission/my-cookie-permission.plugin';
import ExampleAjaxPlugin from './example-plugin/example-plugin.plugin'
import CalculatorPlugin from "./calculator-plugin/calculator-plugin.plugin";

const PluginManager=window.PluginManager;
PluginManager.register('CgsPlugin',CgsPlugin,'[data-example-plugin]');
PluginManager.override('CookiePermission', MyCookiePermission, '[data-cookie-permission]');
if (module.hot) {
    module.hot.accept();
}
PluginManager.register('ExampleAjaxPlugin',ExampleAjaxPlugin,'[data-ajax-helper]');
PluginManager.register('CalculatorPlugin',CalculatorPlugin,'[data-calculator]');