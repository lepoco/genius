// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using Genius.Data.Contexts;
using Genius.Expert.Interfaces;
using Genius.Services;
using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Http;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using Microsoft.Extensions.Hosting;

namespace Genius;

public class Startup
{
    public const string DatabaseName = "GeniusExpert.db";

    public string DbExpertPath { get; internal set; }

    public Startup(IConfiguration configuration)
    {
        SetupDatabase(configuration);
    }

    public void SetupDatabase(IConfiguration configuration)
    {
        string expertDatabasePath = configuration.GetConnectionString("ExpertDatabase");

        if (String.IsNullOrEmpty(expertDatabasePath))
        {
            var folder = Environment.SpecialFolder.LocalApplicationData;
            var path = Environment.GetFolderPath(folder);

            expertDatabasePath = System.IO.Path.Join(path, DatabaseName);
        }

        DbExpertPath = expertDatabasePath;
    }

    public void ConfigureServices(IServiceCollection services)
    {
        services.AddDbContext<IExpertContext, ExpertContext>(options =>
        {
            options.UseSqlite($"Data Source={DbExpertPath}");
        });

        services.AddScoped<IExpertService, GeniusService>();

        services.AddGrpc();
    }

    public void Configure(IApplicationBuilder app, IWebHostEnvironment env)
    {
        if (env.IsDevelopment())
            app.UseDeveloperExceptionPage();

        app.UseRouting();

        app.UseEndpoints(endpoints =>
        {
            endpoints.MapGrpcService<GrpcExpertService>();
            endpoints.MapGrpcService<GrpcSolverService>();

            endpoints.MapGet("/", async context =>
            {
                await context.Response.WriteAsync("Communication with gRPC endpoints must be made through a gRPC client.");
            });
        });
    }
}
