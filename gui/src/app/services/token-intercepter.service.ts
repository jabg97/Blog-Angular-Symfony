import {
  HttpInterceptor
} from "@angular/common/http";
import {
  Injectable,
  Injector
} from "@angular/core";

import {
  ApiService
} from "./api.service";

@Injectable({
  providedIn: "root"
})
export class TokenIntercepterService implements HttpInterceptor {
  constructor(private apiService: ApiService, private injector: Injector) {}

  intercept(req, next) {
    let tokenizedReq = req.clone({
      setHeaders: {
        Authorization: `bearer ${this.injector.get(ApiService).getUser}`
      }
    });
    return next.handle(tokenizedReq);
  }
}
