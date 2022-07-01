import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class ProductCartItem extends Component {
    protected provider: AjaxProvider;
    protected removeForm: HTMLFormElement;
    protected removeButton: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.provider = <AjaxProvider>document.getElementsByClassName(this.providerClassName)[0];
        this.removeForm = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__remove-form`)[0];
        this.removeButton = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__remove-button`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.removeButton.addEventListener('click', (event: Event) => this.onClickRemoveButton(event));
    }

    protected onClickRemoveButton(event: Event): void {
        event.preventDefault();
        const removeForm = this.removeForm;

        if (!removeForm) {
            return;
        }

        const removeFormData = new FormData(removeForm);
        this.provider.setAttribute('url', removeForm.action);
        this.provider.fetch(removeFormData);
    }

    protected get providerClassName(): string {
        return this.getAttribute('provider-class-name');
    }
}
