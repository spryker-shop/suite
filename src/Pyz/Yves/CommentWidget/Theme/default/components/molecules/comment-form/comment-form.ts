import CommentFormCore from 'CommentWidget/components/molecules/comment-form/comment-form';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class CommentForm extends CommentFormCore {
    protected messageField: HTMLTextAreaElement;
    protected provider: AjaxProvider;

    protected readyCallback(): void {
        this.provider = <AjaxProvider>document.getElementsByClassName(this.providerClassName)[0];
        this.messageField = <HTMLTextAreaElement>this.getElementsByClassName(`${this.jsName}__message`)[0];

        super.readyCallback();
    }

    protected async onButtonFormClick(event: Event, action: string) {
        event.preventDefault();
        const formData = new FormData(this.commentForm);
        this.provider.setAttribute('url', action);
        await this.provider.fetch(formData);

        if (this.shouldResetFieldAfterRequest) {
            this.messageField.value = '';
        }
    }

    protected get providerClassName(): string {
        return this.getAttribute('provider-class-name');
    }

    protected get shouldResetFieldAfterRequest(): boolean {
        return this.hasAttribute('should-reset-field-after-request');
    }
}
