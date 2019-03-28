import Component from 'ShopUi/models/component';

export default class ColorSelector extends Component {
    /**
     * Collection of the anchor elements which on hover change images of product.
     */
    colors: HTMLAnchorElement[];

    /**
     * Collection of the product image elements.
     */
    images: HTMLImageElement[];

    protected readyCallback(): void {
        this.colors = <HTMLAnchorElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__color`));
        this.images = <HTMLImageElement[]>Array.from(document.querySelectorAll(this.targetImageSelector));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.colors.forEach((color: HTMLAnchorElement) => {
            color.addEventListener('mouseenter', (event: Event) => this.onColorSelection(event));
        });
    }

    protected onColorSelection(event: Event): void {
        event.preventDefault();
        const color = <HTMLAnchorElement>event.currentTarget;
        const imageSrc = color.getAttribute('data-image-src');
        this.changeActiveColor(color);
        this.changeImage(imageSrc);
    }

    /**
     * Adds or removes active class names from the product color elements.
     * @param newColor HTMLAnchorElement corresponding to the product color element that was selected.
     */
    changeActiveColor(newColor: HTMLAnchorElement): void {
        this.colors.forEach((color: HTMLAnchorElement) => {
            color.classList.remove(`${this.name}__color--active`);
        });

        newColor.classList.add(`${this.name}__color--active`);
    }

    /**
     * Replaced src attribute for product image elements.
     * @param newImageSrc A new scr string for product image.
     */
    changeImage(newImageSrc: string): void {
        this.images.forEach((image: HTMLImageElement) => {
            if (image.src !== newImageSrc) {
                image.src = newImageSrc;
            }
        });
    }

    /**
     * Gets a target image class name.
     */
    get targetImageSelector(): string {
        return this.getAttribute('target-image-selector');
    }
}
