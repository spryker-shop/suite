import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { OverwrittenDummyCardComponent } from './overwritten-dummy-card.component';
import { CardModule } from '@spryker/card';

/* tslint:disable:no-unnecessary-class */
@NgModule({
    imports: [CommonModule, CardModule],
    declarations: [OverwrittenDummyCardComponent],
    exports: [OverwrittenDummyCardComponent],
})
export class OverwrittenDummyCardModule {}
