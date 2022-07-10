import '../css/app.scss';
import {Dropdown} from 'bootstrap';
/**
 *
 * @typedef {Object} VideoFormResponse
 * @property {string} code
 * @property {Object} errors
 * @property {string} html
 */

document.addEventListener('DOMContentLoaded', () => {
    new App();
});
class App{
    construct(){
        this.enableDropdowns();
        this.handleCommentForm();
    }

   enableDropdowns(){
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.map(function (dropdownToggleEl) {
        return new Dropdown(dropdownToggleEl);
    }); 
}

    handleCommentForm(){
    const commentForm = document.querySelector('form.comment-form');
    if (null === commentForm) {
        return;
    }
    commentForm.addEventListener('submit',async (e) => {
        e.preventDefault();
        const response = await fetch ('/ajax/comments', {
            method: 'POST',
            body: new FormData(e.target)
        });
    if(!response.ok){
            return;
        }
    const json = await response.json();
    if(json.code ==='COMMENT_ADDED_SUCCESSFULLY'){
const commentList=document.querySelector('.comment-list');
const commentCount=document.querySelector('.comment-count');
const commentContent=document.querySelector('#comment-content');
commentList.insertAjacentHTML('afterbegin',json.message);
commentCount.innerText=json.numberOfComments;
commentContent.value='';
    }
    
    });
}
}
/* code video form */
const formVideo = document.querySelector('#form_video');
const videosList = document.querySelector('#videos_list');
formVideo.addEventListener('submit', function (e) {
    fetch(this.action, {
        body: new FormData(e.target),
        method: 'POST'
    })
        .then(response => response.json())
        .then(json => {
            handleResponse(json);
        });
});
const handleResponse = function (response) {
    removeErrors();
    switch(response.code) {
        case 'VIDEO_ADDED_SUCCESSFULLY':
            videosList.innerHTML += response.html;
            break;
        case 'VIDEO_INVALID_FORM':
            handleErrors(response.errors);
            break;
    }
}
const removeErrors = function() {
    const invalidFeedbackElements = document.querySelectorAll('.invalid-feedback');
    const isInvalidElements = document.querySelectorAll('.is-invalid');

    invalidFeedbackElements.forEach(errorElement => errorElement.remove());
    isInvalidElements.forEach(isInvalidElement => isInvalidElement.classList.remove('is-invalid'));
}
/**
 *
 * @param {Object} errors
 */
 const handleErrors = function(errors) {
    if (errors.length === 0) return;

    for (const key in errors) {
        let element = document.querySelector(`#video_${key}`);
        element.classList.add('is-invalid');

        let div = document.createElement('div');
        div.classList.add('invalid-feedback', 'd-block');
        div.innerText = errors[key];

        element.after(div);
    }
}
