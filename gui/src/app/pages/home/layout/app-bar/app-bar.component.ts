import {
  Component,
  OnInit
} from "@angular/core";
import {
  ScreenBreakpointService
} from "src/app/services/breakpoint.service";
import {
  ApiService
} from "../../../../services/api.service";
import {
  Router
} from "@angular/router";
import {
  MatSnackBar
} from '@angular/material/snack-bar';
@Component({
  selector: "app-app-bar",
  templateUrl: "./app-bar.component.html",
  styleUrls: ["./app-bar.component.css"]
})
export class AppBarComponent implements OnInit {
  constructor(
    public BP: ScreenBreakpointService,
    private _api: ApiService,
    private snackBar: MatSnackBar,
    private router: Router
  ) {
    this.query = this._api.getQuery;
  }

  visible = true;
  query: string;
  is_auth = false;
  search() {
    localStorage.setItem('query', this.query);
    this.router.navigateByUrl('query/' + btoa(this.query));
  }

  open() {
    this.visible = !this.visible;
  }

  logout() {
    this._api.logout();
    this.is_auth = false;
  }

  ngOnInit() {
    if (this._api.isLoggedIn) {
      this.is_auth = true;
    }
  }
}
