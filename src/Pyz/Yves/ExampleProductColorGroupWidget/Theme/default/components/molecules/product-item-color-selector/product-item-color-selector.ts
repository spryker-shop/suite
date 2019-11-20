import Component from 'ShopUi/models/component';

export default class ProductItemColorSelector extends Component {
    protected triggers: HTMLElement[];

    protected readyCallback(): void {
        this.triggers = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__item`));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((element: HTMLElement) => {
            element.addEventListener('mouseenter', (event: Event) => this.onTriggerSelection(event));
        });
    }

    protected onTriggerSelection(event: Event): void {
        event.preventDefault();
        const currentSelection = <HTMLElement>event.currentTarget;

        this.setActiveItemSelection(currentSelection);
    }

    protected setActiveItemSelection(currentSelection: HTMLElement): void {
        this.triggers.forEach((element: HTMLElement) => {
            element.classList.remove(this.activeItemClassName);
        });

        currentSelection.classList.add(this.activeItemClassName);
    }

    protected get activeItemClassName(): string {
        return this.getAttribute('active-item-class-name');
    }
}
