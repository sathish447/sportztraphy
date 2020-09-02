import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PlayerCreditsComponent } from './player-credits.component';

describe('PlayerCreditsComponent', () => {
  let component: PlayerCreditsComponent;
  let fixture: ComponentFixture<PlayerCreditsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PlayerCreditsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PlayerCreditsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
