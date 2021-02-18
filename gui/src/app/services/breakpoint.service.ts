import {
  Injectable
} from "@angular/core";
import {
  BreakpointObserver,
  BreakpointState
} from "@angular/cdk/layout";
import {
  BehaviorSubject
} from "rxjs";

export enum Breakpoints {
  "XS" = "xs",
  "SM" = "sm",
  "MD" = "md",
  "LG" = "lg",
  "XL" = "xl"
}

@Injectable({
  providedIn: "root"
})
export class ScreenBreakpointService {
  private xsBreakpoint = ["(max-width:575.98px)"];
  private smBreakpoint = ["(min-width:576px) and (max-width:767.98px)"];
  private mdBreakpoint = ["(min-width:768px) and (max-width:991.98px)"];
  private lgBreakpoint = ["(min-width:992px) and (max-width:1199.98px)"];
  private xlBreakpoint = "(min-width:1200px)";

  private states;

  get state() {
    return this.states;
  }

  get isXS() {
    return this.states == Breakpoints.XS;
  }
  get isSM() {
    return this.states == Breakpoints.SM;
  }
  get isMD() {
    return this.states == Breakpoints.MD;
  }
  get isLG() {
    return this.states == Breakpoints.LG;
  }
  get isXL() {
    return this.states == Breakpoints.XL;
  }

  private screenSizeObserver = new BehaviorSubject < Breakpoints[] > ([
    Breakpoints.XS
  ]);

  constructor(private breakpointObserver: BreakpointObserver) {
    this.initObservers();
    this.screenSizeObserver.subscribe({
      next: state => (this.states = state)
    });
  }

  private initObservers() {
    this.breakpointObserver
      .observe(this.xsBreakpoint)
      .subscribe((state: BreakpointState) => {
        if (state.matches) {
          this.screenSizeObserver.next([Breakpoints.XS]);
        }
      });
    this.breakpointObserver
      .observe(this.smBreakpoint)
      .subscribe((state: BreakpointState) => {
        if (state.matches) {
          this.screenSizeObserver.next([Breakpoints.SM]);
        }
      });
    this.breakpointObserver
      .observe(this.mdBreakpoint)
      .subscribe((state: BreakpointState) => {
        if (state.matches) {
          this.screenSizeObserver.next([Breakpoints.MD]);
        }
      });
    this.breakpointObserver
      .observe(this.lgBreakpoint)
      .subscribe((state: BreakpointState) => {
        if (state.matches) {
          this.screenSizeObserver.next([Breakpoints.LG]);
        }
      });
    this.breakpointObserver
      .observe(this.xlBreakpoint)
      .subscribe((state: BreakpointState) => {
        if (state.matches) {
          this.screenSizeObserver.next([Breakpoints.XL]);
        }
      });
  }
}
