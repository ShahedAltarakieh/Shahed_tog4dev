import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NotfoundRedirectComponent } from './notfound-redirect.component';

describe('NotfoundRedirectComponent', () => {
  let component: NotfoundRedirectComponent;
  let fixture: ComponentFixture<NotfoundRedirectComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NotfoundRedirectComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NotfoundRedirectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
