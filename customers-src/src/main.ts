import {enableProdMode} from '@angular/core';

(window as any).global = window;

import * as Moment from 'moment';
(window as any).moment = Moment;

import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

import { AppMainModule } from './app/app-main.module';
import { environment } from './environments/environment';

if (environment.production) {
    enableProdMode();
}

platformBrowserDynamic().bootstrapModule(AppMainModule);
