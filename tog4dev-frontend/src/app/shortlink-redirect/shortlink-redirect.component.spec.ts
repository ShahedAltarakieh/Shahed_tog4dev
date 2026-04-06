import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShortlinkRedirectComponent } from './shortlink-redirect.component';

describe('ShortlinkRedirectComponent', () => {
  let component: ShortlinkRedirectComponent;
  let fixture: ComponentFixture<ShortlinkRedirectComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ShortlinkRedirectComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ShortlinkRedirectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
