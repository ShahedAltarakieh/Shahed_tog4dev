import { TestBed } from '@angular/core/testing';

import { OurStoriesService } from './our-stories.service';

describe('OurStoriesService', () => {
  let service: OurStoriesService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(OurStoriesService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
