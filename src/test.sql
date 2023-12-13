SELECT * FROM program AS p JOIN actor AS a WHERE p.title OR p.a.lastname LIKE '%os%' ORDER BY p.title ASC; 

SELECT p.title FROM program AS p
JOIN actor_program AS j ON p.id=j.program_id
JOIN actor AS a ON a.id=j.actor_id
WHERE a.lastname LIKE '%harr%' OR p.title LIKE '%harr%' ORDER BY p.title ASC;