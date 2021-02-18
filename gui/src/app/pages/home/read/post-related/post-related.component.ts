import {
  Component,
  OnInit,
  Input
} from "@angular/core";

@Component({
  selector: "app-post-related",
  templateUrl: "./post-related.component.html",
  styleUrls: ["./post-related.component.css"],
})
export class PostRelatedComponent implements OnInit {
  constructor() {}

  @Input() posts: Array < any > ;
  @Input() later: Array < any > ;
  @Input() url_asset: Array < any > ;
  
  ngOnInit() {}
}
