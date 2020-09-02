import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PointsystemComponent } from './pointsystem.component';

describe('PointsystemComponent', () => {
  let component: PointsystemComponent;
  let fixture: ComponentFixture<PointsystemComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PointsystemComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PointsystemComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
