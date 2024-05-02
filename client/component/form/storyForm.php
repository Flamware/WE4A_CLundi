<?php
function displayStoryForm(){
    ?>
<section id="submit-story">
    <label for="story">Votre post :</label>
    <textarea id="story" rows="4" required></textarea>
    <button id="submit-story-btn">Partager</button>
</section>
<?php
}
?>
<style>
#submit-story {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
        border: 2px solid;
        border-radius: 10px;
        background-color: #b6bbc4;
    }

    #submit-story label {
        margin-bottom: 10px;
    }

    #submit-story textarea {
        width: 100%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    #submit-story button {
        padding: 5px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #submit-story button:hover {
        background-color: #0056b3;
    }
</style>
