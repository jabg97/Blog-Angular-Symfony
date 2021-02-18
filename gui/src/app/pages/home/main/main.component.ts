import {
  Component,
  OnInit
} from "@angular/core";
import {
  ActivatedRoute,
  Router,
  ParamMap
} from "@angular/router";

@Component({
  templateUrl: "./main.component.html",
  styleUrls: ["./main.component.css"],
  selector: "app-main"
})
export class MainComponent implements OnInit {

  constructor(private router: Router) {
  }

  ngOnInit() {
  }
}
