import {
  Routes,
  RouterModule
} from "@angular/router";
import {
  NgModule
} from "@angular/core";


import {
  AppBarComponent
} from "./pages/home/layout/app-bar/app-bar.component";

import {
  MainComponent
} from "./pages/home/main/main.component";
import {
  AuthComponent
} from "./pages/auth/auth.component";
import {
  HomeComponent
} from "./pages/home/home.component";
import {
  ContactComponent
} from "./pages/home/contact/contact.component";
import {
  BlogComponent
} from "./pages/home/blog/blog.component";
import {
  AuthGuard
} from "./auth.guard";
import {
  ReadComponent
} from "./pages/home/read/read.component";
import {
  ProfileComponent
} from "./pages/home/profile/profile.component";
import {
  UploadComponent
} from "./pages/home/upload/upload.component";

const routes: Routes = [{
    path: "",
    component: HomeComponent,
    // canActivate: [AuthGuard],
    children: [{
        path: "",
        component: MainComponent,
      },
      {
        path: "contact",
        component: ContactComponent,
      },
      {
        path: "blog",
        component: BlogComponent,
      },
      {
        path: "query/:id",
        component: BlogComponent,
      },
      {
        path: "read/:id",
        component: ReadComponent,
      },
      {
        path: "profile",
        component: ProfileComponent,
      },
      {
        path: "profile/:id",
        component: ProfileComponent,
      },
      {
        path: "upload",
        component: UploadComponent,
      },{
        path: "upload/:id",
        component: UploadComponent,
      }
    ],
  },
  {
    path: "auth",
    component: AuthComponent,
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
export const RoutingComponents = [
  ReadComponent,
  AppBarComponent,
  AuthComponent,
  HomeComponent,
  MainComponent,
  ContactComponent,
  BlogComponent
];
