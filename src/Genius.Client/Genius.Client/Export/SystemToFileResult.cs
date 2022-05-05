// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Microsoft.AspNetCore.Mvc;
using System.IO;
using System.Text.Json;
using System.Threading.Tasks;

namespace Genius.Client.Export
{
    public class SystemToFileResult : FileResult
    {
        private static string ExporterVersion = "1.0.0";

        private readonly ExportExpertModel _exportModel;

        public SystemToFileResult(ExportExpertModel exportModel) : base("text/genius")
        {
            FileDownloadName = exportModel.System.Guid + ".genius";
            _exportModel = exportModel;
        }

        public override async Task ExecuteResultAsync(ActionContext context)
        {
            var response = context.HttpContext.Response;
            context.HttpContext.Response.Headers.Add("Content-Disposition",
                new[] { "attachment; filename=" + FileDownloadName });

            await using var streamWriter = new StreamWriter(response.Body);

            await streamWriter.WriteLineAsync(
                JsonSerializer.Serialize(_exportModel, typeof(ExportExpertModel), new JsonSerializerOptions
                {
                    WriteIndented = true,
                })
            );

            await streamWriter.FlushAsync();
        }
    }
}