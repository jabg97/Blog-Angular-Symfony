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
import {
  NgForm
} from '@angular/forms';
@Component({
  templateUrl: "./contact.component.html",
  styleUrls: ["./contact.component.css"],
  selector: "app-contact"
})
export class ContactComponent implements OnInit {

  constructor(private router: Router,
    private activatedRoute: ActivatedRoute,
    private _api: ApiService,
    private snackBar: MatSnackBar,
    private spinner: NgxSpinnerService) {

  }
  ContactSubmit(fl: NgForm) {
    const snackBar = this.snackBar;
    const router = this.router;
    if (fl.valid) {
      this.spinner.show();
      this._api.send(fl.value).subscribe(data => {
        console.log(data);
        this.spinner.hide();
        if (data.status == 200) {
          fl.resetForm();
          snackBar.open(data.message, '', {
            duration: 2000
          });
        } else {
          snackBar.open(data.message, '', {
            duration: 2000
          });
        }

      });
    } else {
      this.snackBar.open('Formulario Incompleto.', '', {
        duration: 2000
      });
    }
  }
  ngOnInit() {

   
  }
}
