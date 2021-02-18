import {
  BrowserAnimationsModule
} from "@angular/platform-browser/animations";
import {
  HttpClientModule,
  HTTP_INTERCEPTORS
} from "@angular/common/http";
import {
  FormsModule,
  ReactiveFormsModule
} from "@angular/forms";
import {
  BrowserModule
} from "@angular/platform-browser";
import {
  FlexLayoutModule
} from "@angular/flex-layout";
import {
  LayoutModule
} from "@angular/cdk/layout";
import {
  NgModule
} from "@angular/core";


import {
  PostRelatedComponent
} from "./pages/home/read/post-related/post-related.component";
import {
  ReadComponent
} from "./pages/home/read/read.component";
import {
  TokenIntercepterService
} from "./services/token-intercepter.service";
import {
  AppRoutingModule,
  RoutingComponents
} from "./app-routing.module";
import {
  ScreenBreakpointService
} from "./services/breakpoint.service";
import {
  MaterialModule
} from "./components/material/material.module";
import {
  ApiService
} from "./services/api.service";
import {
  AppComponent
} from "./app.component";
//import { AuthGuard } from "./auth.guard";
import {
  ProfileComponent
} from './pages/home/profile/profile.component';
import {
  UploadComponent
} from './pages/home/upload/upload.component';
import {
  MatSnackBarModule
} from '@angular/material/snack-bar';
import {
  NgxSpinnerModule
} from "ngx-spinner";

@NgModule({
  declarations: [
    AppComponent,
    RoutingComponents,
    ReadComponent,
    PostRelatedComponent,
    ProfileComponent,
    UploadComponent,
  ],
  imports: [
    BrowserAnimationsModule,
    ReactiveFormsModule,
    AppRoutingModule,
    HttpClientModule,
    FlexLayoutModule,
    MaterialModule,
    BrowserModule,
    LayoutModule,
    FormsModule,
    MatSnackBarModule,
    NgxSpinnerModule
  ],
  bootstrap: [AppComponent],
  providers: [
    TokenIntercepterService,
    ScreenBreakpointService,
    ApiService,
    //AuthGuard,
    {
      useClass: TokenIntercepterService,
      provide: HTTP_INTERCEPTORS,
      multi: true,
    },
  ],
})
export class AppModule {}
