        <form action="gestion_titres.php" method="post" enctype="multipart/form-data">


                <table id="input-table">
                    <thead>
                        <tr>
                            <th class="listing id_row">#</th>
                            <th class="listing">{_T string="Short form"}</th>
                            <th class="listing">{_T string="Long form"}</th>
                            <th class="listing">{_T string="Actions"}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td class="listing">&nbsp;</td>
                            <td class="listing left">
                                <input size="20" type="text" name="short_label"/>
                            </td>
                            <td class="listing left">
                                <input size="20" type="text" name="long_label"/>
                            </td>
                            <td class="listing center">
                                <input type="hidden" name="new" value="1" />
                                <input type="submit" name="valid" id="btnadd" value="{_T string="Add"}"/>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
            {foreach from=$titles_list item=title name=alltitles}
                        <tr class="{if $smarty.foreach.alltitles.iteration % 2 eq 0}even{else}odd{/if}">
                            <td class="listing">{$title->id}</td>
                            <td class="listing left">{$title->short}</td>
                            <td class="listing left">{$title->long}</td>
                            <td class="listing center actions_row">

                                <a href="edit_title.php?id={$title->id}">
                                    <img src="{$template_subdir}images/icon-edit.png" alt="{_T string="Edit '%s' title" pattern="/%s/" replace=$title->short}" title="{_T string="Edit '%s' title" pattern="/%s/" replace=$title->short}" width="16" height="16"/>
                                </a>
                                <a onclick="return confirm('{_T string="Do you really want to delete this entry?"|escape:"javascript"}')" href="gestion_titres.php?del={$title->id}">
                                    <img src="{$template_subdir}images/icon-trash.png" alt="{_T string="Delete '%s' title" pattern="/%s/" replace=$title->short}" title="{_T string="Delete '%s' title" pattern="/%s/" replace=$title->short}" width="16" height="16" />
                                </a>
                            </td>
                        </tr>
            {/foreach}
                    </tbody>
                </table>
        </form>
