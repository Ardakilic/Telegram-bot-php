START TRANSACTION;
SET standard_conforming_strings=off;
SET escape_string_warning=off;
SET CONSTRAINTS ALL DEFERRED;

INSERT INTO "responses"
VALUES
    (1,'myDemoBot','hello','text','Hello to you, too!','y','n'),
    (2,'myDemoBot','photo','image','metal.jpg','n','n'),
    (3,'myDemoBot','audio','audio','balls.mp3','n','n'),
    (4,'myDemoBot','video','video','mov_bbb.mp4','n','n'),
    (5,'myDemoBot','sticker','sticker','gnu.png','n','n');

COMMIT;