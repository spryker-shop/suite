import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';

/* tslint:disable:no-unnecessary-class */
@Component({
    selector: 'mp-dummy-card',
    templateUrl: './overwritten-dummy-card.component.html',
    styleUrls: ['./overwritten-dummy-card.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class OverwrittenDummyCardComponent {}
