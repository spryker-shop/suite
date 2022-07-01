import AjaxRendererCore from 'ShopUi/components/molecules/ajax-renderer/ajax-renderer';
import { EVENT_UPDATE_DYNAMIC_MESSAGES } from 'ShopUi/components/organisms/dynamic-notification-area/dynamic-notification-area';

export default class AjaxRenderer extends AjaxRendererCore {
    render(): void {
        if (this.provider.xhr.response && this.provider.xhr.getResponseHeader('Content-Type') === 'application/json') {
            const parsedResponse = JSON.parse(this.provider.xhr.response);
            const dynamicNotificationCustomEvent = new CustomEvent(EVENT_UPDATE_DYNAMIC_MESSAGES, {
                detail: parsedResponse.messages,
            });
            document.dispatchEvent(dynamicNotificationCustomEvent);

            return;
        }

        super.render();
    }
}
