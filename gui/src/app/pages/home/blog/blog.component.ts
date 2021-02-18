import {
  Component,
  OnInit
} from "@angular/core";
import {
  ActivatedRoute,
  Router,
  ParamMap
} from "@angular/router";
import {
  ApiService
} from "../../../services/api.service";
import {
  MatSnackBar
} from '@angular/material/snack-bar';
import {
  NgxSpinnerService
} from "ngx-spinner";
@Component({
  templateUrl: "./blog.component.html",
  styleUrls: ["./blog.component.css"],
  selector: "app-blog"
})
export class BlogComponent implements OnInit {
  posts: Array < any > ;
  title: any;
  constructor(private router: Router,
    private activatedRoute: ActivatedRoute,
    private _api: ApiService,
    private snackBar: MatSnackBar,
    private spinner: NgxSpinnerService) {
  

    this.posts = [];
    this.title = "";
  }

  ngOnInit() {

    this.activatedRoute.paramMap.subscribe((param) => {

      const snackBar = this.snackBar;
      if (this.router.url.includes("/query")) {
        this.spinner.show();
        this._api.query(this._api.getQuery).subscribe(data => {
          console.log(data);
          this.spinner.hide();
          if (data.status == 200) {
            this.posts = data.posts;
            this.title = "Resultado Busqueda";
          } else {
            snackBar.open(data.message, '', {
              duration: 2000
            });
          }

        });
      } else {
        this.spinner.show();
        this._api.index().subscribe(data => {
          console.log(data);
          this.spinner.hide();
          if (data.status == 200) {
            this.posts = data.posts;
            this.title = "Blog";
          } else {
            snackBar.open(data.message, '', {
              duration: 2000
            });
          }

        });
      }
    });
  }
}
