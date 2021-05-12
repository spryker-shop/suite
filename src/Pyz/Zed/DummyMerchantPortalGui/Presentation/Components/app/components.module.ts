import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { DummyLayoutComponent, DummyLayoutModule } from '@mp/dummy-merchant-portal-gui';
import { OverwrittenDummyCardComponent } from './overwritten-dummy-card/overwritten-dummy-card.component';
import { OverwrittenDummyCardModule } from './overwritten-dummy-card/overwritten-dummy-card.module';

/* tslint:disable:no-unnecessary-class */
@NgModule({
    imports: [
        WebComponentsModule.withComponents([DummyLayoutComponent, OverwrittenDummyCardComponent]),
        DummyLayoutModule,
        OverwrittenDummyCardModule,
    ],
})
export class ComponentsModule {}
