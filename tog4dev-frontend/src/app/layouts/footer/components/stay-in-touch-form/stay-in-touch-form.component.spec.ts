import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StayInTouchFormComponent } from './stay-in-touch-form.component';

describe('StayInTouchFormComponent', () => {
  let component: StayInTouchFormComponent;
  let fixture: ComponentFixture<StayInTouchFormComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [StayInTouchFormComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(StayInTouchFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
