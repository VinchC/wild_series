import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["body"];

    toggleDarkMode(event) {
        this.bodyTarget.setAttribute('data-bs-theme', 'dark');
    }
}
