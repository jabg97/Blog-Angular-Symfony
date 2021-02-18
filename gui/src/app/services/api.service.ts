import {
  HttpClient,
  HttpErrorResponse
} from "@angular/common/http";
import {
  catchError
} from "rxjs/internal/operators";
import {
  Observable,
  throwError
} from "rxjs";
import {
  Injectable
} from "@angular/core";
import {
  Router
} from "@angular/router";
import {
  MatSnackBar
} from '@angular/material/snack-bar';




@Injectable({
  providedIn: "root"
})
export class ApiService {
  private url: string = "http://localhost/blog/api/public/";
  public url_asset: string = this.url+"upload/img/";

  constructor(private http: HttpClient, private router: Router, private snackBar: MatSnackBar) {}


  login(data): Observable < any > {
    return this.http
      .post < any > (this.url + "auth/login", data)
      .pipe(catchError(this.errorHandler));
  }

  register(data): Observable < any > {
    return this.http
      .post < any > (this.url + "auth/register", data)
      .pipe(catchError(this.errorHandler));
  }

  subscribe(data): Observable < any > {
    return this.http
      .post < any > (this.url + "user/subscribe", data)
      .pipe(catchError(this.errorHandler));
  }

  desubscribe(data): Observable < any > {
    return this.http
      .post < any > (this.url + "user/desubscribe", data)
      .pipe(catchError(this.errorHandler));
  }

  update(data): Observable < any > {
    return this.http
      .post < any > (this.url + "user/update", data)
      .pipe(catchError(this.errorHandler));
  }

  user(id, user): Observable < any > {
    return this.http
      .post < any > (this.url + "user/info" , {
        "id": id,
        "user": user
      })
      .pipe(catchError(this.errorHandler));
  }

  index(): Observable < any > {
    return this.http
      .post < any > (this.url + "post/index", {})
      .pipe(catchError(this.errorHandler));
  }


  query(query): Observable < any > {
    return this.http
      .post < any > (this.url + "post/query", {
        "query": query
      })
      .pipe(catchError(this.errorHandler));
  }

  read(id, user): Observable < any > {
    return this.http
      .post < any > (this.url + "post/read", {
        "id": id,
        "user": user
      })
      .pipe(catchError(this.errorHandler));
  }

  upload(data): Observable < any > {
    return this.http
      .post < any > (this.url + "post/upload", data)
      .pipe(catchError(this.errorHandler));
  }

  like(data): Observable < any > {
    return this.http
      .post < any > (this.url + "post/like", data)
      .pipe(catchError(this.errorHandler));
  }

  dislike(data): Observable < any > {
    return this.http
      .post < any > (this.url + "post/dislike", data)
      .pipe(catchError(this.errorHandler));
  }

  delete(data): Observable < any > {
    return this.http
      .post < any > (this.url + "post/delete", data)
      .pipe(catchError(this.errorHandler));
  }

  post_update(data): Observable < any > {
    return this.http
      .post < any > (this.url + "post/update", data)
      .pipe(catchError(this.errorHandler));
  }

  comment(data): Observable < any > {
    return this.http
      .post < any > (this.url + "comment/publish", data)
      .pipe(catchError(this.errorHandler));
  }

  comment_like(data): Observable < any > {
    return this.http
      .post < any > (this.url + "comment/like", data)
      .pipe(catchError(this.errorHandler));
  }

  comment_dislike(data): Observable < any > {
    return this.http
      .post < any > (this.url + "comment/dislike", data)
      .pipe(catchError(this.errorHandler));
  }
  comment_delete(data): Observable < any > {
    return this.http
      .post < any > (this.url + "comment/delete", data)
      .pipe(catchError(this.errorHandler));
  }

  send(data): Observable < any > {
    return this.http
      .post < any > (this.url + "message/send", data)
      .pipe(catchError(this.errorHandler));
  }
  msg_delete(data): Observable < any > {
    return this.http
      .post < any > (this.url + "message/delete", data)
      .pipe(catchError(this.errorHandler));
  }

  errorHandler(error: HttpErrorResponse) {
    this.snackBar.open("Server error: " + error.error.message + ".", '', {
      duration: 2000
    });
    console.log(error.error);
    return throwError(error.error || "Server error");
  }

  get isLoggedIn(): boolean {
    return !!localStorage.getItem("user");
  }

  get getUser(): string {
    return localStorage.getItem("user");
  }

  get getUrl(): string {
    return this.url;
  }

  get getQuery(): string {
    return (localStorage.getItem("query")) ? localStorage.getItem("query") : "";
  }

  logout() {
    localStorage.removeItem("user");
    //window.location.reload();
  }
}
