IF OBJECT_ID('SP_GERA_UPDATE') IS NOT NULL
	DROP PROCEDURE SP_GERA_UPDATE;
GO
 CREATE PROCEDURE SP_GERA_UPDATE(@TABLE VARCHAR(32))AS
 DECLARE @ARGS NVARCHAR(MAX),
		@VALUES NVARCHAR(MAX),
		@LENGTH NVARCHAR(MAX),
		@SQL NVARCHAR(MAX),
		@COLUMN_NAME NVARCHAR(32),
		@DATA_TYPE NVARCHAR(32),
		@PK_IDENTITY NVARCHAR(32), -- CHAVE PRIMARIA DA TABELA
		@MSG nVARCHAR(150)

		-- CASO A TABELA NÃO EXISTA É MANDADO UM ERRO COM A MENSAGEM:
		IF NOT EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES TAB WHERE TAB.TABLE_NAME = @TABLE)
		BEGIN
		SET @MSG = (SELECT T.TEXT FROM typeError T WHERE T.id = 1)
		INSERT INTO error(errorId) VALUES(1)
		RAISERROR(@MSG,1,4)
		RETURN;
		END

DECLARE CT CURSOR FOR
	SELECT COLUMN_NAME, DATA_TYPE,CHARACTER_MAXIMUM_LENGTH
	FROM INFORMATION_SCHEMA.COLUMNS
	WHERE TABLE_NAME = @TABLE
	ORDER BY ORDINAL_POSITION
BEGIN
	SET @ARGS = ''
	SET @LENGTH = ''
	SET @SQL = ''
	SET @PK_IDENTITY = (SELECT COLUNA FROM ALL_PRIMARY_KEY_COLUMNS WHERE TABELA = @TABLE)--NOME DA CHAVE PRIMARIA

	OPEN CT
	FETCH CT INTO @COLUMN_NAME,@DATA_TYPE,@LENGTH
	WHILE(@@FETCH_STATUS = 0) 
	BEGIN
		IF NOT EXISTS(SELECT * FROM All_Primary_Key_Columns WHERE TABELA = @TABLE AND COLUNA = @COLUMN_NAME)
		BEGIN
			SET @ARGS = '@' +@COLUMN_NAME + ' ' + @DATA_TYPE
			IF(@LENGTH = '-1')-- PARA O VARBINARY (MAX), EM QUE O MAX FICA A -1 QUANDO É FEITO O FETCH
			BEGIN
				SET @LENGTH = 'MAX'
			END
			IF(@LENGTH IS NOT NULL) BEGIN
				SET @ARGS += '(' + @LENGTH + ')'
			END
			SET @SQL = 'CREATE PROCEDURE sp_'+ @TABLE + '_' +@COLUMN_NAME +'_update('+ @args+',@PRIMARYKEYID BIGINT)AS
			BEGIN
				IF NOT EXISTS(SELECT * FROM ' + @TABLE + ' WHERE ' +@PK_IDENTITY+' = @PRIMARYKEYID)
				BEGIN
					INSERT INTO ERROR(ERRORID) VALUES (4);
					THROW 51000, '''+(SELECT CONCAT('ERRO ',T.id, ' - ', T.TEXT) FROM typeError T WHERE T.id = 4) +''',1;
				END
				
				IF EXISTS(SELECT * FROM ALL_FOREIGN_KEY_COLUMNS WHERE TABELA = ''' + @TABLE + ''' AND COLUNA = '''+ @COLUMN_NAME + ''')
				BEGIN
					INSERT INTO ERROR(ERRORID) VALUES(5);
					THROW 51000, '''+(SELECT CONCAT('ERRO ',T.id, ' - ', T.TEXT) FROM typeError T WHERE T.id = 5) +''',1;
				END
				
				UPDATE '+ @TABLE + '
				SET ' + @COLUMN_NAME + ' =  @'+@COLUMN_NAME+'
				WHERE '+ @PK_IDENTITY +' = @PRIMARYKEYID
			END
			'
			EXEC SP_EXECUTESQL @SQL

			PRINT @SQL
		END
		FETCH CT INTO @COLUMN_NAME,@DATA_TYPE,@LENGTH 
	END
	CLOSE CT; DEALLOCATE CT
END
Go