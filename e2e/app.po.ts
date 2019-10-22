import {} from 'jasmine';
import { browser, by, element } from 'protractor';

export class AppPage {
  navigateTo() {
    return browser.get('/');
  }

  getParagraphText() {
    return element(by.xpath('//*[@id="index-banner"]/div[1]/div/div[2]/h5')).getText();
  }
}
