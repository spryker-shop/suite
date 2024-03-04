import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-dummy-card',
    templateUrl: './overwritten-dummy-card.component.html',
    styleUrls: ['./overwritten-dummy-card.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class OverwrittenDummyCardComponent {}
