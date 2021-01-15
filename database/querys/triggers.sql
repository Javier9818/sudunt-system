DELIMITER $$ #CAMBIAR SUDUNT POR NOMBRE DE BD
USE `sudunt`$$
CREATE
TRIGGER `sudunt`.`update`
BEFORE UPDATE ON `sudunt`.`votes` #aqui puedes poner antes o despues del update
FOR EACH ROW
BEGIN
		/*Paso de variables para un mejor control*/
		set @res1 = ''; set @res2 = ''; set @res3 = ''; set @res4 = ''; set @res5 = ''; set @res6 = '';
		/*Sacamos info de la ip donde se ejecuta la accion de UPDATE*/
		select host as IP INTO @ipcl from information_schema.processlist WHERE ID=connection_id();
		#concatenamos los campos de la tabla a auditar y verificamos que no sean null, en caso de que los campos sean null agregamos un espacio
		#las variables (new,old)son de mysql, el valor old es el que ya se tenia en la tabla y el new es el valor que se modifico
		
		#Valores viejos
		SET @oldq = CONCAT (' id ',ifnull(OLD.id,''),
														' ip ',ifnull(OLD.ip,''),
														' response ',ifnull(OLD.response,''),
														' form_id ',ifnull(OLD.form_id,''),
                                                        ' created_at ',ifnull(OLD.created_at,''),
                                                        ' updated_at ',ifnull(OLD.updated_at,''));
		#Valores nuevos
        SET @newq = CONCAT (' id ',ifnull(new.id,''),
														' ip ',ifnull(new.ip,''),
														' response ',ifnull(new.response,''),
														' form_id ',ifnull(new.form_id,''),
                                                        ' created_at ',ifnull(new.created_at,''),
                                                        ' updated_at ',ifnull(new.updated_at,''));

	#guardamos en una variable los valores que unicamente cambiaron													
    IF OLD.id <> new.id THEN set @res1 = CONCAT ('Cambio id ',ifnull(OLD.id,''), ' a: ',ifnull(new.id,'')); END IF;
	IF OLD.ip <> new.ip THEN set @res2 = CONCAT ('Cambio ip ',ifnull(OLD.ip,''), ' a: ',ifnull(new.ip,'')); END IF;
	IF OLD.response <> new.response THEN set @res3 = CONCAT ('Cambio response ',ifnull(OLD.response,''), ' a: ',ifnull(new.response,'')); END IF;
	IF OLD.form_id <> new.form_id THEN set @res4 = CONCAT ('Cambio form_id ',ifnull(OLD.form_id,''), ' a: ',ifnull(new.form_id,'')); END IF;
    IF OLD.created_at <> new.created_at THEN set @res5 = CONCAT ('Cambio created_at ',ifnull(OLD.created_at,''), ' a: ',ifnull(new.created_at,'')); END IF;
    IF OLD.updated_at <> new.updated_at THEN set @res6 = CONCAT ('Cambio updated_at ',ifnull(OLD.updated_at,''), ' a: ',ifnull(new.updated_at,'')); END IF;
	SET @resC=CONCAT(ifnull(@res1,''),'|',ifnull(@res2,''),'|',ifnull(@res3,''),'|',ifnull(@res4,''),'|',ifnull(@res5,''),'|',ifnull(@res6,''));

	#insertamos en nuestra tabla de log la informacion
	INSERT INTO sudunt.logs (old,new,usuario,typo,fecha,tabla,valor_alterado,ip)                
	VALUES (@oldq ,@newq,CURRENT_USER,"UPDATE",NOW(),"votes",ifnull(@resC,'No cambio nada'),@ipcl);
END$$



#log de insertados(Nuevos registros)
DELIMITER $$
USE `sudunt`$$
CREATE
TRIGGER `sudunt`.`incert`
BEFORE INSERT ON `sudunt`.`votes`
FOR EACH ROW
BEGIN
    SET @oldq = '';
    SET @newq = CONCAT (' id ',ifnull(new.id,''),
	' id ',ifnull(new.id,''),
	' response ',ifnull(new.response,''),
	' form_id ',ifnull(new.form_id,''));
	INSERT INTO sudunt.logs (old,new,usuario,typo,fecha,tabla)                
    VALUES (@oldq ,@newq,CURRENT_USER,"INSERT",NOW(),"votes");
END$$

#log de Borrados
DELIMITER $$
USE `sudunt`$$
CREATE
TRIGGER `sudunt`.`delete`
AFTER DELETE ON `sudunt`.`votes`
FOR EACH ROW
BEGIN
	SET @newq = '';
    SET @oldq = CONCAT (' id ',ifnull(OLD.id,''),
	' ip ',ifnull(OLD.ip,''),
	' response ',ifnull(OLD.response,''),
	' form_id ',ifnull(OLD.form_id,''));
	INSERT INTO sudunt.logs (old,new,usuario,typo,fecha,tabla)                
    VALUES (@oldq ,@newq,CURRENT_USER,"DELETE",NOW(),"votes");
END$$