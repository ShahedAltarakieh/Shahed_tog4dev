import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NgoverseSliderComponent } from './ngoverse-slider.component';

describe('NgoverseSliderComponent', () => {
  let component: NgoverseSliderComponent;
  let fixture: ComponentFixture<NgoverseSliderComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NgoverseSliderComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NgoverseSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
