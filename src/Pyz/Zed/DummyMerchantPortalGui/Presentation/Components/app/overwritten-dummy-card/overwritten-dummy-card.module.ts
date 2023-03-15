import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';
import { OverwrittenDummyCardComponent } from './overwritten-dummy-card.component';

@NgModule({
    imports: [CommonModule, CardModule],
    declarations: [OverwrittenDummyCardComponent],
    exports: [OverwrittenDummyCardComponent],
})
export class OverwrittenDummyCardModule {}
