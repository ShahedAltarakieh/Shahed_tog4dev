import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NgoverseComponent } from './ngoverse.component';

describe('NgoverseComponent', () => {
  let component: NgoverseComponent;
  let fixture: ComponentFixture<NgoverseComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NgoverseComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NgoverseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
