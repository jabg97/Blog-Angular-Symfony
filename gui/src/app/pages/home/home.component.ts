import {
  Component,
  OnInit
} from "@angular/core";

import {
  onMainContentChange
} from "../../components/animation/nav-animation";
import {
  ScreenBreakpointService
} from "../../services/breakpoint.service";

@Component({
  selector: "app-home",
  templateUrl: "./home.component.html",
  styleUrls: ["./home.component.css"],
  animations: [onMainContentChange]
})
export class HomeComponent implements OnInit {
  constructor(
    public BP: ScreenBreakpointService
  ) {}

  ngOnInit() {}
}
