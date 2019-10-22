import {} from 'jasmine';
import { AppPage } from './app.po';
import {beforeEach, describe, it} from 'selenium-webdriver/testing';


describe('renova App', () => {
  let page: AppPage;

  beforeEach(() => {
    page = new AppPage();
  });

  it('should display welcome message', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual("A modern responsive front-end framework based on Material Design");
  });
});
