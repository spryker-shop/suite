import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class ProductCartItem extends Component {
    protected provider: AjaxProvider;
    protected form: HTMLFormElement;
    protected submitButton: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.provider = <AjaxProvider>document.getElementsByClassName(this.providerClassName)[0];
        this.form = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form`)[0];
        this.submitButton = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__submit-button`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.submitButton.addEventListener('click', (event: Event) => this.onClickRemoveButton(event));
    }

    protected onClickRemoveButton(event: Event): void {
        event.preventDefault();
        const form = this.form;

        if (!form) {
            return;
        }

        const formData = new FormData(form);
        this.provider.setAttribute('url', form.action);
        this.provider.fetch(formData);
    }

    protected get providerClassName(): string {
        return this.getAttribute('provider-class-name');
    }
}
