// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Genius.Client.Export;
using Genius.Protocol;

namespace Genius.Client.Import;

/// <summary>
/// Set of tools for importing.
/// </summary>
public static class SystemImporter
{
    /// <summary>
    /// Tries to add data from <see cref="ExportExpertModel"/> to selected Expert System.
    /// </summary>
    public static async Task<bool> MergeSystemsAsync(Expert.ExpertClient grpcClient, int systemId, ExportExpertModel expertModel)
    {
        // TODO: Well, we need to import it
        return false;
    }

    public static async Task<int> ImportSystemAsync(Expert.ExpertClient grpcClient, ExportExpertModel exportExpertModel)
    {
        var existingSystem = await grpcClient.GetAsync(new ExpertLookupModel { Guid = exportExpertModel.System.Guid });

        if (existingSystem.Id > 0)
            return 0;

        if (String.IsNullOrWhiteSpace(exportExpertModel.System.Name))
            return 0;

        var createdSystem = await grpcClient.CreateAsync(new ExpertModel
        {
            Name = exportExpertModel.System.Name,
            Version = exportExpertModel.System?.Version ?? String.Empty,
            Description = exportExpertModel.System?.Description ?? String.Empty,
            Question = exportExpertModel.System?.Question ?? String.Empty,
            Author = exportExpertModel.System?.Author ?? String.Empty,
            Source = exportExpertModel.System?.Source ?? String.Empty,
            Confidence = exportExpertModel.System?.Confidence ?? 256,
            Type = exportExpertModel.System?.Type ?? Genius.Protocol.SystemType.Conditional,
            CreatedAt = exportExpertModel.System?.CreatedAt ?? DateTime.Now.ToShortDateString(),
            UpdatedAt = DateTime.Now.ToShortDateString()
        });

        if (createdSystem.Id < 1)
            return 0;

        var addedProductsModels = new List<Genius.Protocol.ProductModel> { };
        var addedConditionModels = new List<Genius.Protocol.ConditionModel> { };

        foreach (var singleProduct in exportExpertModel.Products)
        {
            if (String.IsNullOrWhiteSpace(singleProduct.Name))
                continue;

            var addedProduct = await grpcClient.AddProductAsync(new ProductModel
            {
                SystemId = createdSystem.Id,
                Name = singleProduct.Name,
                Description = singleProduct?.Description ?? String.Empty,
                Notes = singleProduct?.Description ?? String.Empty,
            });

            if (addedProduct.Id > 0)
                addedProductsModels.Add(addedProduct);
        }

        foreach (var singleCondition in exportExpertModel.Conditions)
        {
            if (String.IsNullOrWhiteSpace(singleCondition.Name))
                continue;

            var addedCondition = await grpcClient.AddConditionAsync(new ConditionModel
            {
                SystemId = createdSystem.Id,
                Name = singleCondition.Name,
                Description = singleCondition?.Description ?? String.Empty,
            });

            if (addedCondition.Id > 0)
                addedConditionModels.Add(addedCondition);
        }

        // Time consuming, cache above
        foreach (var singleRelation in exportExpertModel.Relations)
        {
            var newRelation = new RelationModel { SystemId = createdSystem.Id };
            var oldProductId = singleRelation.ProductId;
            var oldConditionId = singleRelation.ConditionId;

            var previousProduct
                = exportExpertModel.Products.FirstOrDefault(prod => prod.Id == oldProductId, new ProductModel());
            var previousCondition
                = exportExpertModel.Conditions.FirstOrDefault(con => con.Id == oldConditionId, new ConditionModel());

            if (previousProduct.Id < 0 || previousCondition.Id < 0)
                continue;

            var newProduct =
                addedProductsModels.FirstOrDefault(prod => prod.Name == previousProduct.Name, new ProductModel());
            var newCondition
                = addedConditionModels.FirstOrDefault(con => con.Name == previousCondition.Name, new ConditionModel());

            if (newProduct.Id < 0 || newCondition.Id < 0)
                continue;

            newRelation.ProductId = newProduct.Id;
            newRelation.ConditionId = newCondition.Id;

            await grpcClient.AddRelationAsync(newRelation);
        }

        return createdSystem.Id;
    }
}
