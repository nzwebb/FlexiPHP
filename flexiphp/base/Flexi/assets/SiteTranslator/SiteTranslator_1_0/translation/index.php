<?
/*
$Author: colineberhardt $
$Date: 2004/11/12 16:30:20 $
$Revision: 1.1 $
$Name:  $

Copyright (C) 2004  C.N.Eberhardt (webmaster@jugglingdb.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
?>
<html>
<head>
<title>Translation page</title>
</head>
<frameset cols=320,* frameborder=1 framespacing=1>
	<frame frameborder=0 framespacing=0 marginheight=0 marginwidth=0 name="menu"	src="menu.php">
	<frame frameborder=1 framespacing=1 name="edit" src="translate.php?key=<? echo $_GET["key"]; ?>">
</frameset>
</html>