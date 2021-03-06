void Ex3()
{
	/*Setting Variables*/
	int length;
	char *divstr, *firststr, *secstr;
	/*Accepts the size of strings and strings*/
	printf("enter the size of the string");
	scanf("%d", &length);
	divstr = (char*)malloc(length * sizeof(char));
	printf("enter the div string ");
	gets();
	gets(divstr);
	printf("enter the size of the string");
	scanf("%d", &length);
	firststr = (char*)malloc(length * sizeof(char));
	printf("enter the first string ");
	gets();
	gets(firststr);
	printf("enter the size of the string");
	scanf("%d", &length);
	secstr = (char*)malloc(length * sizeof(char));
	printf("enter the second string ");
	gets();
	gets(secstr);
	printf("\n%d", divchecker(divstr, firststr, secstr));
	free(divstr);
	free(firststr);
	free(secstr);
}
/* A function that checks whether the string "difference" in addition to the second string creates the first string */

int divchecker(char *divstr, char *firststr, char *secstr)
{
	/*Setting Variables*/

	int *counterletters[26] = { 0 }, i;
	/*A loop that enters an array counts the number of times the letter appears in the first string rather than the second string*/
	while (*(firststr) != '\0' || *(secstr) != '\0')
	{
		/*Checks if the end of the first string has been reached*/
		if (*firststr)
		{
			counterletters[*firststr - 'a']++;
			firststr++;
		}
		/*Checks if it has reached the end of the second string*/
		if (*secstr)
		{
			counterletters[*secstr - 'a']--;
			secstr++;
		}
	}
	/*A loop that checks whether the existing letters in the first string and not the second exists in the "difference" string*/
	while (*(divstr) != '\0')
	{

		if (counterletters[*divstr - 'a'] < 1)
			return 0;
		else counterletters[*divstr - 'a']--;
		divstr++;
	}
	/*A loop that checks whether there are any letters in the first string and were not in the string the "difference" or the second string*/
	for (i = 0;i < 26;i++)
		if (counterletters[i] > 0)
			return 0;

	return 1;
}