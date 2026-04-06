import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SubscriptionPolicyComponent } from './subscription-policy.component';

describe('SubscriptionPolicyComponent', () => {
  let component: SubscriptionPolicyComponent;
  let fixture: ComponentFixture<SubscriptionPolicyComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SubscriptionPolicyComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SubscriptionPolicyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
