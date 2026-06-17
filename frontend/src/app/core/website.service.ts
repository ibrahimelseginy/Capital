import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class WebsiteService {

  private apiUrl = 'http://127.0.0.1:8000/api/v1/website/home';
  public backendUrl = 'http://127.0.0.1:8000/storage/';

  constructor(private http: HttpClient) { }

  getHomeContent(): Observable<any> {
    return this.http.get<any>(this.apiUrl);
  }
}
